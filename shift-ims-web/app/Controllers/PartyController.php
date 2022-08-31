<?php

namespace App\Controllers;

use App\Models\PartyModel;
use App\Entities\Party;

class PartyController extends BaseController
{
    protected $type = 0;
    protected $viewPath = '';
    protected $redirectUrl = '';

    public function index()
    {
        $parties = $this->getPartyModel()->getAll($this->type);
        return view($this->viewPath . '/index', [
            'items' => $parties
        ]);
    }

    public function edit($id)
    {
        $model = $this->getPartyModel();
        if ($id == 0) {
            $party = new Party();
            $party->active = true;
        }
        else {
            $party = $model->find($id);
            if (!$party) {
                return redirect()->to(base_url($this->redirectUrl))
                    ->with('warning', 'Item tidak ditemukan.');
            }
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            $party->fill($this->request->getPost());
            $party->type = $this->type;

            if ($party->name == '') {
                $errors['name'] = 'Nama harus diisi.';
            }

            if ($model->exists($party->name, $party->id, $this->type)) {
                $errors['name'] = 'Nama sudah digunakan, harap gunakan nama lain.';
            }

            if (empty($errors)) {
                $model->save($party);
                return redirect()->to(base_url($this->redirectUrl))
                    ->with('info', 'Berhasil disimpan.');
            }
        }
        
        return view($this->viewPath . '/edit', [
            'data' => $party,
            'errors' => $errors,
        ]);
    }

    public function delete($id)
    {
        $model = $this->getPartyModel();
        $party = $model->find($id);
        if ($party->type != $this->type) {
            return redirect()->to(base_url($this->redirectUrl))
                ->with('warning', 'Rekaman tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to(base_url($this->redirectUrl))
            ->with('info', 'Rekaman telah dihapus.');
    }
}
