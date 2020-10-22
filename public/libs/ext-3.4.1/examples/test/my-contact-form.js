
Ext.onReady(function () {
    Ext.QuickTips.init();

    // var apiUrl = "http://localhost:8080";
    var apiUrl = "";

    Ext.namespace('Ext.exampledata');
    Ext.exampledata.priorities = [
        { id: 1, name: 'Low' },
        { id: 2, name: 'Medium' },
        { id: 3, name: 'High' },
    ];

    var priorityRecord = Ext.data.Record.create([ // creates a subclass of Ext.data.Record
        { name: 'id', mapping: 'id' },
        { name: 'name', mapping: 'name' },
    ]);

    var priorityStore = new Ext.data.ArrayStore({
        fields: priorityRecord,
        data: Ext.exampledata.priorities,
        id: 'id'
    });
    var simple = null;

    initForm();

    function initForm() {
        // turn on validation errors beside the field globally
        Ext.form.Field.prototype.msgTarget = 'side';

        simple = new Ext.FormPanel({
            labelAlign: 'top',
            title: 'My Contact Form',
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
                        fieldLabel: 'Company',
                        name: 'company',
                        anchor:'95%'
                    }]
                },{
                    columnWidth:.5,
                    layout: 'form',
                    border:false,
                    items: [
                        new Ext.form.ComboBox({
                            fieldLabel: 'Priority',
                            store: priorityStore,
                            displayField: 'name',
                            valueField: 'id',
                            value: '',
                            typeAhead: true,
                            mode: 'local',
                            forceSelection: true,
                            triggerAction: 'all',
                            emptyText: 'Select a priority',
                            selectOnFocus: true,
                            allowBlank: false,
                            name: 'priority_id',
                            editable: false,
                            anchor:'95%'
                        }),{
                            xtype:'textfield',
                            fieldLabel: 'Email',
                            name: 'email',
                            vtype:'email',
                            anchor:'95%'
                        }
                    ]
                }]
            },{
                xtype:'tabpanel',
                plain:true,
                activeTab: 0,
                height:230,
                /*
                  By turning off deferred rendering we are guaranteeing that the
                  form fields within tabs that are not activated will still be rendered.
                  This is often important when creating multi-tabbed forms.
                */
                deferredRender: false,
                defaults:{bodyStyle:'padding:10px'},
                items:[{
                    title:'Personal Details',
                    layout:'form',
                    defaults: {width: 220},
                    defaultType: 'textfield',
    
                    items: [{
                        xtype: 'datefield',
                        name: 'birthdate',
                        fieldLabel: 'Birth Date'
                    }, {
                        fieldLabel: 'Profession',
                        name: 'profession',
                        value: ''
                    }, {
                        xtype: 'textarea',
                        name: 'notes',
                        fieldLabel: 'Notes',
                        anchor:'100%'
                    }]
                },{
                    title:'Phone Numbers',
                    layout:'form',
                    defaults: {width: 230},
                    defaultType: 'textfield',
    
                    items: [{
                        fieldLabel: 'Home',
                        name: 'phone_home',
                        value: '(888) 555-1212'
                    },{
                        fieldLabel: 'Business',
                        name: 'phone_business'
                    },{
                        fieldLabel: 'Mobile',
                        name: 'phone_mobile'
                    },{
                        fieldLabel: 'Fax',
                        name: 'fax'
                    }]
                },{
                    cls:'x-plain',
                    title:'Biography',
                    layout:'fit',
                    items: {
                        xtype:'htmleditor',
                        id:'bio2',
                        name: 'biography',
                        fieldLabel:'Biography'
                    }
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
                        url: apiUrl + '/form',
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
    }

    var formRecord = Ext.data.Record.create([ // creates a subclass of Ext.data.Record
        { name: 'id', mapping: 'id' },
        { name: 'name', mapping: 'name' },
        { name: 'company', mapping: 'company' },
        { name: 'email', mapping: 'email' },
        { name: 'birthdate', mapping: 'birthdate', type: 'date', dateFormat: 'Y-m-d' },
        { name: 'profession', mapping: 'profession' },
        { name: 'notes', mapping: 'notes' },
        { name: 'phone_home', mapping: 'phone_home' },
        { name: 'phone_business', mapping: 'phone_business' },
        { name: 'phone_mobile', mapping: 'phone_mobile' },
        { name: 'fax', mapping: 'fax' },
        { name: 'biography', mapping: 'biography' },
        { name: 'priority_id', mapping: 'priority_id', type: 'float' },
        { name: 'created_at', mapping: 'created_at', type: 'date', dateFormat: 'Y-m-d H:i:s' },
        { name: 'updated_at', mapping: 'updated_at', type: 'date', dateFormat: 'Y-m-d H:i:s' },
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
        function priority(val) {

            var p = priorityStore.getById(val);
            var pName = p ? p.get('name') : '';

            if (val === 1) {
                return '<span style="color:green;">' + pName + '</span>';
            } else if (val === 2) {
                return '<span style="color:orange;">' + pName + '</span>';
            } else if (val === 3) {
                return '<span style="color:red;">' + pName + '</span>';
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
                    header: 'Priority',
                    width: 75,
                    sortable: true,
                    renderer: priority,
                    dataIndex: 'priority_id'
                },
                {
                    header: 'Created At',
                    width: 120,
                    sortable: true,
                    renderer: Ext.util.Format.dateRenderer('Y-m-d h:iA'),
                    dataIndex: 'created_at'
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
                                        url: apiUrl + '/form/' + rec.get('id'),
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
            title: 'Contact List',
            // config options for stateful behavior
            stateful: true,
            stateId: 'grid'
        });

        // render the grid to the specified div in the page
        grid.render('grid-example');
    }

    function loadForms() {
        Ext.Ajax.request({
            url: apiUrl + '/form/payload',
            success: function (response) {
                const data = JSON.parse(response.responseText);
                store.loadData(data.forms);
                store.loadData(data.forms);
                priorityStore.loadData(data.priorities);
            },
            headers: {},
            params: {}
        });
    }
});
