//CLASS
function ypFireBase(table) 
{
    this.tb = firebase.database().ref(table);
}
ypFireBase.prototype = {
    insert: function(data) 
    {
        for (var k in data)
        {
            this.tb.child(k).set(data[k]);
        }
    },
    update: function(id, value)
    {
        var row = this.tb.child(id);
        row.once("value", function(snapshot) {
            snapshot.ref.update(value);
        });
    },
    delete: function(id)
    {
        this.tb.child(id).remove();
    },
    get: function(id)
    {
        return id ? this.tb.child(id) : this.tb;
    },
    cek:function(id)
    {
        var row = this.tb.child(id);
        row.on("value", function(snapshot) {
            var n = snapshot.numChildren();
            return n ? snapshot : false;
        });
    },
    offline: function(id)
    {
        return this.tb.child(id).onDisconnect();
    },
    online: function(id){
        this.tb.child(id).update({"online":true});
    },
    isLogin: function()
    {
        return firebase.auth().currentUser ? true : false;
    },
    authID: function()
    {
        return firebase.auth().currentUser.providerData[0].uid;
    },
    auth: function(type)
    {
        if ( type=='google' )
		{
			var provider = new firebase.auth.GoogleAuthProvider();
			
			provider.addScope('https://www.googleapis.com/auth/contacts.readonly');
		}
		else if( type=='facebook' )
		{
			var provider = new firebase.auth.FacebookAuthProvider();
			
			provider.addScope('user_birthday');
		}
		else if( type=='twitter' )
		{
			var provider = new firebase.auth.TwitterAuthProvider();
		}
        else if (type=='logout')
        {
            firebase.auth().signOut();
            $.get( $('#form-login').data('logout'), function( data ) {
                window.location.reload();
            });
            return false;
        }

        var t = this;

		firebase.auth().signInWithPopup(provider).then(function(result) {
		
			var token = result.credential.accessToken;

			var user = result.user;

			t.authSave(type, user);
		});
    },
    authSave: function(type, data)
    {
        var rowData = {
            'id' : data.providerData[0].uid,
            'nama' : data.providerData[0].displayName,
            'image' : data.providerData[0].photoURL,
            'email' : data.email ? data.email : data.providerData[0].email,
            'type' : type,
            '_token': $('#form-login').data('token')
        };

        $.ajax({
            type		: 'POST',
            url			: $('#form-login').data('store'),
            data        : rowData,
            beforeSend	: function(xhr) { loading(1) },
            success		: function(dt){                
                swal(dt.data_user,
                function(){
                    window.location.reload();
                });
            },
        }).done(function(){ loading(0);  });
    },
    memberOnline: function(){
        $.ajax({
            type		: 'POST',
            url			: $('#form-login').data('ping'),
            beforeSend	: function(xhr) { loading(1) },
            success		: function(dt){
                
                if(dt.response) $('.user_login_info').replaceWith(htmlDecode(dt.response));
                if(dt.data_user) swal(dt.data_user);

            },
        }).done(function(){ loading(0); });
    }
}
function htmlDecode(value) {
    return $("<textarea/>").html(value).text();
}