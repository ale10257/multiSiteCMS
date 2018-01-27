<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:11
 */

namespace app\core\accessSites;

class AccessService
{
    /** @var AccessRepository  */
    private $_repository;
    /** @var AccessForm  */
    private $_form;

    public function __construct(AccessRepository $repository, AccessForm $form)
    {
        $this->_repository = $repository;
        $this->_form = $form;
    }

    /**
     * @param AccessForm $form
     * @return int
     */
    public function create(AccessForm $form)
    {
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();

        return $this->_repository->id;
    }

    /**
     * @param AccessForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(AccessForm $form, int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();
    }

    /**
     * @return AccessForm
     */
    public function getNewForm()
    {
        return $this->_form;
    }

    /**
     * @param int $id
     * @return AccessForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_form->createUpdateForm($this->_repository);
        return $this->_form;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function delete(int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->deleteItem();
    }
}