<?php

namespace LaravelCMF\Admin\Resources\Fields;

use Illuminate\Http\Request;

class Password extends ResourceField
{
    protected $form_template = 'admin.fields.form.password';
    protected $passwordConfirm;
    protected $password;

    public function validate()
    {
        if($this->password) {
            if($this->password !== $this->passwordConfirm) {
                $this->addError('The passwords do not match.');
            }
        }
        return parent::validate();
    }

    public function processRequest(Request $request)
    {
        $passwordData       = $request->input($this->getRequestKey(), null);
        if($passwordData && !empty($passwordData['value']) && !empty($passwordData['confirm'])) {
            $this->password = $passwordData['value'];
            $this->passwordConfirm = $passwordData['confirm'];
            $this->processedData = $this->encrypt($passwordData['value']);
        }
    }

    public function encrypt($value)
    {
        return bcrypt($value);
    }
}