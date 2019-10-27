<?php


namespace OpenFram\Form;

use function OpenFram\escape_to_html as h;

class InputFileField extends InputField
{
    protected $value;


    public function buildWidget()
    {
        $widget = "";


        $widget .= "<div class=\"fileinput fileinput-new text-center\" data-provides=\"fileinput\">";
        $widget .= " <div class=\"fileinput-new thumbnail img-raised\"> ";
        $widget .= "</div > ";
        $widget .= "<div class=\"fileinput-preview fileinput-exists thumbnail img-raised\">";
        if ($this->value !== null && is_string($this->value)) {
                $url = htmlspecialchars($this->value);
                $widget .= "<img src=\"" . $url . "\" class=\" img-fluid \" >";
        }

        $widget .= "</div>";
        $widget .= "<div>";
        $widget .= "<span class=\"btn btn-raised btn-round btn-default btn-file\">";
        $widget .= " <span class=\"fileinput-new\">Select image</span>";
        $widget .= " <span class=\"fileinput-exists\">Change</span>";
        $widget .= "<input type=\"file\" id=\"myFile\" value='' name=\"".$this->attributes['name'] ."\" />";
        $widget .= "</span>";
        $widget .= "<a href=\"#\" class=\"btn btn-danger btn-round fileinput-exists\" data-dismiss=\"fileinput\"><i class=\"fa fa-times\"></i> Remove</a>";
        $widget .= "</div>";
        $widget .= "</div>";

        if (!empty($this->errorMessage)) {
            $widget .= '<span class="material-icons form-control-feedback">clear</span>';
            $widget .= '<small class = "text-danger"> * ' . $this->errorMessage . '</small>';
        }

        return $widget;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
