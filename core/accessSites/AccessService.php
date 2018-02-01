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
    private $repository;
    /** @var AccessForm  */
    private $form;

    public function __construct(AccessRepository $repository, AccessForm $form)
    {
        $this->repository = $repository;
        $this->form = $form;
    }

    /**
     * @param AccessForm $form
     * @return int
     */
    public function create(AccessForm $form)
    {
        $this->repository->insertValues($form);
        $this->repository->saveItem();

        return $this->repository->id;
    }

    /**
     * @param AccessForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(AccessForm $form, int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->repository->insertValues($form);
        $this->repository->saveItem();
    }

    /**
     * @return AccessForm
     */
    public function getNewForm()
    {
        return $this->form;
    }

    /**
     * @param int $id
     * @return AccessForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->form->createUpdateForm($this->repository);
        return $this->form;
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
        $this->repository = $this->repository->getItem($id);
        $this->repository->deleteItem();
    }
}