dojo.provide('sf.Admin');

sf = {
    gridLayouts : {
        user : [
            {field: 'userId', width : '100px', name : 'User Id'},
            {field: 'username', width : '100px', name : 'Username'},
            {field: 'firstName', width : '150px', name : 'First Name'},
            {field: 'lastName', width : '150px', name : 'Surname'},
            {field: 'email', width : '200px', name : 'Email'},
            {field: 'role', width : '100px', name : 'Role'}
        ]
    }
}

dojo.declare(
    'sf.Admin',
    null,
    {
        menuClick : function(e)
        {
            dojo.stopEvent(e);
            location.href = e.target.href;
        },

        tabCreate : function(e)
        {
            dojo.stopEvent(e);
            var tabId = e.target.innerHTML.replace(/\s/g, '').toLowerCase() + 'Tab';
            var tc = dijit.byId("AdminTabs");

            if (!dijit.byId(tabId)) {

                var pane = new dijit.layout.ContentPane({
                    id: tabId,
                    title: e.target.innerHTML,
                    href: e.target.href,
                    closable: true
                });

                tc.addChild(pane);
            }

            tc.selectChild(tabId);
        }
    }
);

dojo.addOnLoad(function(){
    /*dojo.subscribe("AdminTabs-selectChild", function(child){
        console.log("A new child was selected:", child);
        child.refresh();
    });*/

    setTimeout(function(){
        var loader = dojo.byId("loader");
        dojo.fadeOut({
            node: loader,
            duration: 500,
            onEnd: function(){
                loader.style.display = "none";
                if (dijit.byId('login')) {
                    login.show();
                }
            }
        }).play();
    }, 250);
});
