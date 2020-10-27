
Ext.onReady(function () {
    Ext.QuickTips.init();

    // var apiUrl = "http://localhost:8080";
    var apiUrl = "";

    var simple = null;

    initForm();

    function initForm() {
        // turn on validation errors beside the field globally
        Ext.form.Field.prototype.msgTarget = 'side';

        simple = new Ext.FormPanel({
            labelAlign: 'top',
            title: 'My Contact Form',
            bodyStyle:'padding: 7px;',
            // width: 300,
            items: [
                {
                    xtype:'textfield',
                    fieldLabel: 'Email',
                    name: 'email',
                    vtype:'email',
                    anchor:'1'
                },
                {
                    xtype:'textfield',
                    type: 'password',
                    fieldLabel: 'Password',
                    inputType: 'password',
                    allowBlank: false,
                    name: 'password',
                    anchor:'1'
                }
            ],
    
            buttons: [{
                text: 'Submit',
                handler: function (b) {
                    var f = simple.getForm();
                    var vals = f.getFieldValues();

                    if (!f.isValid()) {
                        f.markInvalid();
                        return;
                    }

                    var btn = this;
                    btn.setDisabled(true);

                    Ext.Ajax.request({
                        url: apiUrl + '/login/login',
                        method: 'POST',
                        success: function (response) {
                            window.location.href = apiUrl+'/';
                        },
                        failure: (response) => {
                            btn.setDisabled(false);
                            Ext.Msg.alert('Status', 'Problem to login. Please, try again.');
                        },
                        headers: {},
                        jsonData: vals
                    });
                }
            }]
        });

        simple.render('login-form');
    }

});
