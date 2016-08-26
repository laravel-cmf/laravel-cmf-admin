<?php

namespace LaravelCMF\Admin\Resources\Fields;

use Illuminate\Http\Request;

class Checkbox extends ResourceField
{
    protected $form_template = 'admin.fields.form.checkbox';

    public function processRequest(Request $request)
    {
        $processedData       = $request->input($this->getRequestKey(), null);
        $this->setProcessedData($processedData ? 1 : 0);
    }

    /**
     * Return the rendered value of this resource field to display in list views.
     * @return mixed
     */
    public function displayList()
    {
        return $this->getValue() ? 'Yes' : 'No';
    }

}