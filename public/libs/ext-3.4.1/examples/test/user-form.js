
Ext.onReady(function () {
    Ext.QuickTips.init();

    // var apiUrl = "http://localhost:8080";
    var apiUrl = "";

    Ext.namespace('Ext.exampledata');
    Ext.exampledata.roles = [
        { id: 'admin', name: 'Admin' },
        { id: 'member', name: 'Member' },
    ];

    var roleRecord = Ext.data.Record.create([ // creates a subclass of Ext.data.Record
        { name: 'id', mapping: 'id' },
        { name: 'name', mapping: 'name' },
    ]);

    var roleStore = new Ext.data.ArrayStore({
        fields: roleRecord,
        data: Ext.exampledata.roles,
        id: 'id'
    });
    var simple = null;

    initForm();

    function initForm() {
        // turn on validation errors beside the field globally
        Ext.form.Field.prototype.msgTarget = 'side';

        simple = new Ext.FormPanel({
            labelAlign: 'top',
            title: 'User Form',
            bodyStyle:'padding:5px',
            width: 600,
            items: [{
                layout:'column',
                border:false,
                items:[{
                    columnWidth:.5,
                    layout: 'form',
                    border:false,
                    items: [{
                        xtype: 'hidden',
                        name: 'id',
                        value: null
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Name',
                        allowBlank: false,
                        name: 'name',
                        anchor:'95%'
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Email',
                        name: 'email',
                        vtype:'email',
                        anchor:'95%'
                    },
                    new Ext.form.ComboBox({
                        fieldLabel: 'Role',
                        store: roleStore,
                        displayField: 'name',
                        valueField: 'id',
                        value: '',
                        typeAhead: true,
                        mode: 'local',
                        forceSelection: true,
                        triggerAction: 'all',
                        emptyText: 'Select a role',
                        selectOnFocus: true,
                        allowBlank: false,
                        name: 'role',
                        editable: false,
                        anchor:'95%'
                    })]
                },{
                    columnWidth:.5,
                    layout: 'form',
                    border:false,
                    items: [
                        {
                            xtype:'textfield',
                            type: 'password',
                            fieldLabel: 'Password',
                            inputType: 'password',
                            allowBlank: false,
                            name: 'password',
                            anchor:'1'
                        },
                        {
                            xtype:'textfield',
                            type: 'password',
                            fieldLabel: 'Password Confirmation',
                            inputType: 'password',
                            allowBlank: false,
                            name: 'password_confirmation',
                            anchor:'1'
                        }
                    ]
                }]
            }],
    
            buttons: [{
                text: 'Save',
                handler: function (b) {
                    var f = simple.getForm();
                    var vals = f.getFieldValues();
                    console.log(vals);

                    if (!f.isValid()) {
                        f.markInvalid();
                        return;
                    }

                    var btn = this;
                    btn.setDisabled(true);
                    gridPanelMask.show();

                    Ext.Ajax.request({
                        url: apiUrl + '/api/user',
                        method: 'POST',
                        success: function (response) {
                            const data = JSON.parse(response.responseText);

                            const newRecord = new formRecord(data, data.id);
                            const index = store.indexOfId(data.id);
                            if (index != -1) {
                                store.removeAt(index);
                                store.insert(index, newRecord);
                            } else {
                                store.add(newRecord);
                            }

                            btn.setDisabled(false);
                            gridPanelMask.hide();

                            simple.getForm().reset();
                        },
                        failure: (response) => {
                            gridPanelMask.hide();
                            btn.setDisabled(false);
                            Ext.Msg.alert('Status', 'There is a problem on saving this item.');
                        },
                        headers: {},
                        jsonData: vals
                    });
                }
            }, {
                text: 'Reset',
                handler: function (b) {
                    simple.getForm().reset();
                }
            }]
        });

        simple.render('form-example');
        setTimeout(() => {
            simple.getForm().reset();
        }, 400);
    }

    var formRecord = Ext.data.Record.create([ // creates a subclass of Ext.data.Record
        { name: 'id', mapping: 'id' },
        { name: 'name', mapping: 'name' },
        { name: 'email', mapping: 'email' },
        { name: 'role', mapping: 'role' },
    ]);

    // create the data store
    var store = new Ext.data.ArrayStore({
        fields: formRecord,
        id: 'id'
    });

    initGrid();
    var gridPanelMask = new Ext.LoadMask('grid-panel', { msg: "Updating..." });

    loadForms();

    function initGrid() {
        /**
         * Custom function used for column renderer
         * @param {Number} val
         */
        function role(val) {

            var p = roleStore.getById(val);
            var pName = p ? p.get('name') : '';

            if (val === 'admin') {
                return '<span style="color:green;">' + pName + '</span>';
            } else if (val === 'member') {
                return '<span style="color:orange;">' + pName + '</span>';
            }
            return val;
        }

        // create the Grid
        var grid = new Ext.grid.GridPanel({
            id: 'grid-panel',
            store: store,
            columns: [
                {
                    header: 'ID',
                    width: 75,
                    sortable: true,
                    dataIndex: 'id'
                },
                {
                    id: 'name',
                    header: 'Name',
                    width: 160,
                    sortable: true,
                    dataIndex: 'name'
                },
                {
                    header: 'Email',
                    width: 120,
                    sortable: true,
                    dataIndex: 'email'
                },
                {
                    header: 'Priority',
                    width: 75,
                    sortable: true,
                    renderer: role,
                    dataIndex: 'role'
                },
                {
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [{
                        icon: '/libs/ext-3.4.1/examples/shared/icons/fam/delete.gif',  // Use a URL in the icon config
                        tooltip: 'Delete',
                        handler: function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);

                            Ext.Msg.confirm('Confirm', 'Are you sure you want delete this item?', function(btn, text){
                                if (btn == 'yes') {
                                    gridPanelMask.show();
                                    //grid.setDisabled(true)

                                    Ext.Ajax.request({
                                        url: apiUrl + '/api/user/' + rec.get('id'),
                                        method: 'DELETE',
                                        success: function (response) {
                                            store.removeAt(rowIndex);
                                            gridPanelMask.hide();
                                            //grid.setDisabled(false)
                                        },
                                        failure: (response) => {
                                            gridPanelMask.hide();
                                            Ext.Msg.alert('Status', 'It wasn\'t possible to remove this item.');
                                        },
                                        headers: {}
                                    });
                                }
                            });
                        }
                    }, {
                        icon: '/libs/ext-3.4.1/examples/shared/icons/fam/cog_edit.png',  // Use a URL in the icon config
                        tooltip: 'Edit',
                        handler: function (grid, rowIndex, colIndex) {
                            var rec = store.getAt(rowIndex);
                            rec.data.password = null;
                            var editFormForm = simple.getForm();
                            editFormForm.reset();
                            editFormForm.setValues(rec.data);
                        }
                    }]
                }
            ],
            stripeRows: true,
            autoExpandColumn: 'name',
            height: 350,
            width: 600,
            title: 'User List',
            // config options for stateful behavior
            stateful: true,
            stateId: 'grid'
        });

        // render the grid to the specified div in the page
        grid.render('grid-example');
    }

    function loadForms() {
        Ext.Ajax.request({
            url: apiUrl + '/api/user',
            success: function (response) {
                const data = JSON.parse(response.responseText);
                store.loadData(data);
                // roleStore.loadData(data.roles);
            },
            headers: {},
            params: {}
        });
    }
});
