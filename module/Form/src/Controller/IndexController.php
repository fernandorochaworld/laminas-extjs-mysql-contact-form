<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Form\Controller;

use Exception;
use Form\Model\Form;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;

class IndexController extends AbstractActionController
{
    protected $table;
    protected $priorityTable;

    public function __construct($table, $priorityTable)
    {
        $this->table = $table;
        $this->priorityTable = $priorityTable;
    }

    public function homeAction()
    {
        return new ViewModel();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isGet()){
            $id = (int) $this->params()->fromRoute('id', 0);
            if ($id) {
                return $this->show($id);
            } else {
                return $this->load();
            }
        } elseif ($request->isPut() || $request->isPost()){
            return $this->save();
        } elseif ($request->isDelete()){
            return $this->delete();
        }
    }

    private function load()
    {
        /* @var $forms AbstractResultSet */
        $forms = $this->table->fetchAll();
        return new JsonModel($forms);
    }

    private function save()
    {
        $request = $this->getRequest();
        $params = json_decode($request->getContent(), true);
        $form = new Form();
        $form->exchangeArray($params);

        $form_id = $this->table->saveData($form);

        $form = $this->table->getData($form_id);

        return new JsonModel((array)$form);
    }

    public function show($id)
    {
        try {
            $form = $this->table->getData($id);
        } catch(Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new JsonModel((array)$form);
    }

    public function payloadAction()
    {
        $forms = $this->table->fetchAll();
        $priorities = $this->priorityTable->fetchAll();

        return new JsonModel([
            'forms' => $forms->toArray(),
            'priorities' => $priorities->toArray()
        ]);
    }

    private function delete()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            throw new RuntimeException('Invalid Id');
        }

        $form = $this->table->getData($id);
        if ($form && $form->id) {
            $this->table->deleteData($id);
            // return new JsonModel((array)$form);
            return new JsonModel([
                'message' => 'Successfully removed.'
            ]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot delete form with identifier %d; does not exist',
                $id
            ));
        }
    }
}
