
var dialog = {
    current_tab:null,
    load:function( url )
    {
        this.current_tab = dialog.loading();
        this.current_tab.load( url );
    },
    loading:function()
    {
        $('#myModal .modal-tab').hide();
        
        if ( this.current_tab === null )
        {
            $('#myModal').html( '<div class="modal-tab"><span class="loading"></span></div>' );
            $('#myModal').modal( 'show' );
            
            
            var self = this;
            $('#myModal').on( 'hidden', function() {
                self.current_tab = null;
            });
        }
        else
            this.current_tab.after( '<div class="modal-tab"><span class="loading"></span></div>' );
        
        return $('#myModal .modal-tab .loading').parent();
    },
    next:function()
    {
        var tab = this.current_tab.next('.modal-tab');
        
        if ( tab !== null )
        {
            this.current_tab = tab;
            $('#myModal .modal-tab').hide();
            this.current_tab.show();
        }
    },
    prev:function()
    {
        var tab = this.current_tab.prev('.modal-tab');
        
        if ( tab !== null )
        {
            this.current_tab = tab;
            $('#myModal .modal-tab').hide();
            this.current_tab.show();
        }
    }
};
