<?php

/**
 *  Class for submit controls.
 *
 *  @author     Alfred TCHONDJO <iriven@yahoo.fr>
 *  @copyright  (c) 2006 - 2011 Iriven France
 *  @package    Controls
 */
class Iriven_FormManager_Submit extends Iriven_FormManager_Control
{

    /**
     *  Adds an <input type="submit"> control to the form.
     *
     *  <b>Do not instantiate this class directly! Use the {@link Iriven_FormManager::add() add()} method instead!</b>
     *
     *  <code>
     *  //  create a new form
     *  $form = new Iriven_FormManager('my_form');
     *
     *  /**
     *   *  add a submit control to the form
     *   *  the "&" symbol is there so that $obj will be a reference to the object in PHP 4
     *   *  for PHP 5+ there is no need for it
     *   {@*}
     *  $obj = &$form->add('submit', 'my_submit', 'Submit');
     *
     *  // don't forget to always call this method before rendering the form
     *  if ($form->validate()) {
     *      // put code here
     *  }
     *
     *  //  output the form using an automatically generated template
     *  $form->render();
     *  </code>
     *
     *  @param  string  $id             Unique name to identify the control in the form.
     *
     *                                  The control's <b>name</b> attribute will be the same as the <b>id</b> attribute!
     *
     *                                  This is the name to be used when referring to the control's value in the
     *                                  POST/GET superglobals, after the form was submitted.
     *
     *                                  This is also the name of the variable to be used in the template file, containing
     *                                  the generated HTML for the control.
     *
     *                                  <code>
     *                                  /**
     *                                   *  in a template file, in order to print the generated HTML
     *                                   *  for a control named "my_submit", one would use:
     *                                   {@*}
     *                                  echo $my_submit;
     *                                  </code>
     *
     *  @param  string  $caption        Caption of the submit button control.
     *
     *  @param  array   $attributes     (Optional) An array of attributes valid for
     *                                  {@link http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.4 input}
     *                                  controls (size, readonly, style, etc)
     *
     *                                  Must be specified as an associative array, in the form of <i>attribute => value</i>.
     *                                  <code>
     *                                  //  setting the "alt" attribute
     *                                  $obj = &$form->add(
     *                                      'submit',
     *                                      'my_submit',
     *                                      'Submit',
     *                                      array(
     *                                          'alt' => 'Click to submit values'
     *                                      )
     *                                  );
     *                                  </code>
     *
     *                                  See {@link Iriven_FormManager_Control::set_attributes() set_attributes()} on how to set
     *                                  attributes, other than through the constructor.
     *
     *                                  The following attributes are automatically set when the control is created and
     *                                  should not be altered manually:<br>
     *                                  <b>type</b>, <b>id</b>, <b>name</b>, <b>value</b>, <b>class</b>
     *
     *  @return void
     */
    function Iriven_FormManager_Submit($id, $caption, $attributes = '')
    {
    
        // call the constructor of the parent class
        parent::Iriven_FormManager_Control();
        
        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'locked',

        );

        // set the default attributes for the submit button control
        // put them in the order you'd like them rendered
        $this->set_attributes(
        
            array(

		        'type'  =>  'submit',
                'name'  =>  $id,
                'id'    =>  $id,
                'value' =>  $caption,
                'class' =>  'submit',

		    )

		);

        // sets user specified attributes for the control
        $this->set_attributes($attributes);

    }
    
    /**
     *  Returns the generated HTML code for the control.
     *
     *  <i>This method is automatically called by the {@link Iriven_FormManager::render() render()} method!</i>
     *
     *  @return string  The generated HTML code for the control
     */
    function toHTML()
    {

        return '<input ' . $this->_render_attributes() . ($this->form_properties['doctype'] == 'xhtml' ? '/' : '') . '>';

    }

}

?>