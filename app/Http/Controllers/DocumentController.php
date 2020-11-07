<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentController extends Controller
{
    //
    // WEB FUNCTION
    //
    public function index(){
        $documents = Document::all();
        return view('dokumen.daftar', ["data"=>$documents]);
    }

    public function create($slug){
        $document = Document::where('slug',$slug)->first();
        if(!$document) return redirect()->back()->withErrors("Dokumen tidak terdaftar");
        $require = array_map(function($item){
            return [
                "slug" => $item,
                "display" => ucwords(preg_replace("/_/"," ", $item))
            ];
        }, $this->getReplaceable($document->id));
        return view('dokumen.unduh', ["slug"=>$slug, "requireable"=>$require]);
    }

    public function upload(){
        return view('dokumen.unggah');
    }

    public function store(Request $request)
    {
        //dd($request->post());
        $request->validate([
            'document' => 'required|max:2048',
            'name' => 'required',
            'category' => 'required'
        ]);
        $newDoc = new Document();
        $newDoc->title = $request->name;
        $newDoc->user = Auth::id() ?? 1;
        $newDoc->category = ucwords($request->category);
        $newDoc->slug = preg_replace('/[^a-zA-Z0-9]/','_',strtolower($request->name));
        $newDoc->save();
        $request->file("document")->storeAs("document", "$newDoc->id.docx");
        return response()->redirectTo(route("dokumen.index"));
    }

    //
    // API FUNCTION
    //
    public function categories()
    {
        $categories = Document::distinct("category")->get();
        return response()->json([
            "status" => "success",
            "total" => $categories->count(),
            "categories" => $categories
        ]);
    }

    public function byCategory($category)
    {
        if (empty($category)) {
            $documents = Document::all();
        } else {
            $documents = Document::where('category', $category);
        }
        return response()->json([
            "status" => "success",
            "documents" => $documents
        ]);
    }

    public function documentInfo($name)
    {
        $document = Document::where('slug', $name)->first();
        if (!$document) return response()->json(["status" => "not found", 404]);
        $replaceable = $this->getReplaceable($document->id);
        return response()->json([
            "title" => $document->title,
            "category" => $document->category,
            "replaceable" => $replaceable
        ]);
    }

    public function makeDocument(Request $request)
    {
        $document = Document::where('slug', $request->name)->first();
        if (!$document) return response()->json(["status" => "not found"], 404);
        $post = $request->post();
        $template = new TemplateProcessor(Storage::path("document/$document->id.docx"));
        foreach ($this->getReplaceable($document->id) as $replaceable) {
            $template->setValue($replaceable, array_key_exists($replaceable, $post) ? $post[$replaceable] : '');
        }
        $filename = Storage::path("document/_temp_".Str::random(8).Carbon::now()->micro.".docx");
        $template->saveAs($filename);
        return response()->download(storage_path()."document/_temp_".Str::random(8).Carbon::now()->micro.".docx", $document->title.".docx")->deleteFileAfterSend(true);
    }

    //
    // HELPER FUNCTION
    //
    private function getReplaceable($id)
    {
        $text = $this->toText($id);
        preg_match_all('/\$\{.+?\}/', $text, $matches, PREG_PATTERN_ORDER);
        return array_map(function ($item) {
            return substr($item, 2, strlen($item) - 3);
        }, $matches[0]);
    }

    private function toText($id)
    {
        $kv_strip_texts = '';
        $kv_texts = '';
        $input_file = Storage::path("document/$id.docx");
        if (!$input_file || !file_exists($input_file)) return false;

        $zip = zip_open($input_file);

        if (!$zip || is_numeric($zip)) return false;


        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $kv_texts .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }

        zip_close($zip);


        $kv_texts = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $kv_texts);
        $kv_texts = str_replace('</w:r></w:p>', "\r\n", $kv_texts);
        $kv_strip_texts = nl2br(strip_tags($kv_texts, ""));

        return $kv_strip_texts;
    }
}
