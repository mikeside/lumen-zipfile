<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpZip\ZipFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileController extends Controller
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem,Finder $finder)
    {
        $this->filesystem = $filesystem;
        $this->finder = $finder;

        if(!$filesystem->exists('file')){
            $filesystem->mkdir('file');
        }
    }

    public function index()
    {
        $this->finder->depth(0)->in('file')->directories();

        $content = [];
        foreach ($this->finder as $item) {

            $count = Finder::create()->depth(0)->in('file/'.$item->getFilename())->count();
            $tmp = array(
                'name' => $item->getFilename(),
                'count' => $count,
            );
            $content[] =  $tmp;
        }

        return view('file/index',compact('content'));
    }

    public function create(Request $request)
    {
        $field = $this->validate($request,
            ['name' => 'required|max:50'],
            [
                'name.required' => '名称不能为空',
                'name.max' => '名称输入限制50字符以内',
            ]
        );

        $projectPath = 'file/'.$field['name'];

        try {
            if($this->filesystem->exists($projectPath)){
                throw new \Exception('项目已存在');
            }
            $this->filesystem->mkdir($projectPath);
        } catch (\Exception $e) {
            return $this->response(0,$e->getMessage());
        }

        return $this->response(1,'新建成功');
    }

    public function delete(Request $request)
    {
        $name = $request->input('name');
        if(empty($name)){
            return $this->response(0,'参数错误');
        }

        try {
            $this->filesystem->remove('file/'.$name);
        } catch (\Exception $e) {
            return $this->response(0,$e->getMessage());
        }

        return $this->response(1,'删除成功');
    }

    public function uploadDemo(Request $request)
    {
        $field = $this->validate($request,
            [
                'name' => 'required',
                'demo' => 'file|mimes:zip'
            ],
            [
                'name.required' => '名称参数错误',
                'demo.file' => '请选择要上传的文件',
                'demo.mimes' => '请上传zip格式的文件',
            ]
        );

        $projectPath = 'file/'.$field['name'];

        //清空旧demo
        $projectCount = $this->finder->depth(0)->in($projectPath)->count();
        if ($projectCount > 0){
            $this->filesystem->remove($projectPath);
            $this->filesystem->mkdir($projectPath);
        }

        //执行上传
        $file = $request->file('demo');
        $fileName = $file->getClientOriginalName();
        $demoPath = $file->move($projectPath,$fileName);

        //todo 解压
        $this->zipExtract($demoPath,$projectPath);

        return $this->response(1,'上传成功');
    }

    protected function zipExtract($zipPath,$toPath)
    {
        $zipFile = new ZipFile();

        try {
            $zipFile->openFile($zipPath);
            $zipFile->extractTo($toPath);

            $rootPath = $toPath.'/'.$zipFile->key();
            if (is_dir($rootPath)){
                $this->moveFile($rootPath,$toPath);
                $this->filesystem->remove($rootPath);
            }
            $this->filesystem->remove($zipPath);

        } catch (\Exception $e) {
            return $this->response(0,$e->getMessage());
        }

        $zipFile->close();
    }

    protected function moveFile($fileFolder, $newPath)
    {
        $finder = Finder::create();
        $finder->depth(0)->in($fileFolder);

        foreach ($finder as $item) {
            $filePath =  $fileFolder . $item->getFilename();
            $this->filesystem->rename($filePath, $newPath . '/' . $item->getFilename());
        }
    }
}
