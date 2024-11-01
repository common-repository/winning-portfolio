(function() {
     /* Register the buttons */
    tinymce.PluginManager.add('wpf_shortcodes', function( editor, url ) {
        editor.addButton( 'wpf_shortcodes', {
              title : 'Insert shortcode',
              image : url + '/../images/logo-color-42.png',
              type : 'menubutton',

              onclick : function() {
                   editor.windowManager.open( {
                      title: 'Add Portfolio List',
                      body: [
                         {
                             type: 'listbox',
                             name: 'hover',
                             label: 'Hover:',
                             'values': [
                                 {text: 'Standard', value: 'standard'},
                                 {text: 'Slide Up', value: 'slide-up'},
                                 {text: 'White Overlay', value: 'white-overlay'}
                             ]
                         },
                         {
                            type: 'textbox',
                            name: 'category',
                            label: 'Include Items from single category, or leave empty for all'
                         },
                         {
                            type: 'textbox',
                            name: 'show',
                            label: 'Limit Portfolio Items (leave empty for unlimited)'
                         },
                         {
                            type: 'listbox',
                            name: 'order',
                            label: 'Order By:',
                               'values': [
                                   {text: 'Title', value: 'title'},
                                   {text: 'Date', value: 'date'},
                                   {text: 'Author', value: 'author'},
                                   {text: 'Random', value: 'random'}
                               ]
                        },
                        {
                            type: 'listbox',
                            name: 'organize',
                            label: 'Organize By',
                               'values': [
                                   {text: 'Ascending', value: 'ASC'},
                                   {text: 'Descending', value: 'DESC'},
                               ]
                        },
                         {
                            type: 'listbox',
                            name: 'columns',
                            label: 'Columns',
                               'values': [
                                   {text: '1 per Row', value: '1'},
                                   {text: '2 per Row', value: '2'},
                                   {text: '3 Per Row', value: '3'},
                                   {text: '4 Per Row', value: '4'},
                                   {text: '5 per Row', value: '5'},
                               ]
                        },
                      ],
                      onsubmit: function( e ) {
                          editor.insertContent( '[wpf-portfolio category="'+e.data.category+'" show="'+e.data.show+'" order="'+e.data.order+'" organize="'+e.data.organize+'" columns="'+e.data.columns+'" hover="'+e.data.hover+'"]');
                      }
                  });
              }

          });
     });
})();