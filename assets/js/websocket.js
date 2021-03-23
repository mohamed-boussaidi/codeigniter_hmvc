var Connection = (function () {
    function Connection() {
        this.open = false;
        this.socket = new WebSocket("ws://" + AppHelper.webSocket.ip + ":" + AppHelper.webSocket.port);
        this.setupConnectionEvents();
    }
    Connection.prototype = {
        setupConnectionEvents: function () {
            var self = this;
            self.socket.onopen = function (e) {
                self.onOpen(e);
            };
            self.socket.onmessage = function (e) {
                self.onEvent(e);
            };
            self.socket.onclose = function (e) {
                self.onClose(e);
            };
        },
        onOpen: function (e) {
            this.open = true;
            console.log("WebSocket : Connected");
        },
        onEvent: function (e) {
            var data = JSON.parse(e.data);
            var event = data.msg.event;
            var message = data.msg.message;
            $.each(AppHelper.onEvent, function( index, value ) {
                eval(value);
                function onShowNotification() {
                    console.log('notification is shown!');
                }

                function onCloseNotification() {
                    console.log('notification is closed!');
                }

                function onClickNotification() {
                    console.log('notification was clicked!');
                }

                function onErrorNotification() {
                    console.error('Error showing notification. You may need to request permission.');
                }

                function onPermissionGranted() {
                    console.log('Permission has been granted by the user');
                    doNotification();
                }

                function onPermissionDenied() {
                    console.warn('Permission has been denied by the user');
                }

                function doNotification() {
                    var myNotification = new Notify('Mind Power Kernel', {
                        body: message,
                        tag: 'My unique id',
                        notifyShow: onShowNotification,
                        notifyClose: onCloseNotification,
                        notifyClick: onClickNotification,
                        notifyError: onErrorNotification,
                        //timeout: 4
                    });

                    myNotification.show();
                }

                if (!Notify.needsPermission) {
                    doNotification();
                } else if (Notify.isSupported()) {
                    Notify.requestPermission(onPermissionGranted, onPermissionDenied);
                }
            });
        },
        onClose: function (e) {
            $.each(AppHelper.onClose, function( index, value ) {
                eval(value);
            });
            this.open = false;
            console.log("WebSocket : Connection error");
        },
        addEvent: function (event, message) {
            if (this.open) {
                this.socket.send(JSON.stringify({
                    msg: {
                        event: event,
                        message: message
                    }
                }));
            }
        }
    };
    return Connection;
})();

var websocket = new Connection();
