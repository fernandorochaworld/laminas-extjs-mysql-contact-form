<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Image\Controller;

use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $images = $this->table->fetchAll();
        // foreach ($images as $image) {
        //     echo $image->getName();
        // }
        // exit();
        return new ViewModel(['images'=>$images]);
    }

    public function addAction()
    {
        $form = new \Image\Form\ImageForm();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['form'=>$form]);
        }

        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );
        
        $image = new \Image\Model\Image();
        $form->setData($post);
        if (!$form->isValid()) {
            exit('id is not correct.');
        }

        // var_dump($post);
        // exit();

        $this->uploadImage($post);

        $image->exchangeArray($post);
        $this->table->saveData($image);
        return $this->redirect()->toRoute('home', [
            'controller' => 'home',
            'action' => 'add'
        ]);

    }

    private function uploadImage(&$data)
    {
        $fileName = 'storage/'.date('Ymd-Hisu').(array_reverse(explode('.',$data["image"]["name"]))[0]);
        $target_file = "/var/www/test_host/uniques/imagelist/public/{$fileName}";
        move_uploaded_file($data["image"]["tmp_name"], $target_file);
        $data["image"] = $fileName;
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            exit('Id invalid');
        }

        try {
            $image = $this->table->getImage($id);
        } catch(Exception $e) {
            exit('error');
        }

        return new ViewModel([
            'image' => $image,
            'id' => $id
        ]);
    }

    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            exit('Id invalid');
        }

        try {
            $image = $this->table->getImage($id);
        } catch(Exception $e) {
            exit('error');
        }

        $form = new \Image\Form\ImageForm();
        $form->bind($image);
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['form'=>$form]);
        }

        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($post);
        if (!$form->isValid()) {
            exit('id is not correct.');
        }

        // var_dump($post);
        // exit();

        $this->uploadImage($post);

        $image->exchangeArray($post);
        $this->table->saveData($image);
        return $this->redirect()->toRoute('image', [
            'controller' => 'home',
            'action' => 'update',
            'id' => $id,
        ]);

    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            exit('Id invalid');
        }

        try {
            $image = $this->table->getImage($id);
        } catch(Exception $e) {
            exit('error');
        }
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['image'=>$image]);
        }

        $delete = $request->getPost('delete', 'No');
        if ($delete == 'Yes') {
            $id = (int) $image->getId();
            $this->table->deleteImage($id);
            return $this->redirect()->toRoute('home');
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
}
