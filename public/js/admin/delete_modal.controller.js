var DeleteModalController = function() {
    var self = this;

    this.title_value = null;
    this.body_value = null;
    this.cancel_value = null;
    this.confirm_value = null;
    this.table_name = null;

    this.init = function() {
        self.changeModalText();

        $(".delete").click(function() {
            self.setModalData($(this).attr('data-id'), $(this).attr('data-value'));
        });

        $(".modal .btn.btn-primary").click(function() {
            if($(this).hasClass('enabled')) {
                $(this).removeClass('enabled');
                self.deleteData($(this).attr('data-id'));
            }
        });
    };

    this.changeModalText = function() {
        $(".modal .modal-title").text(self.title_value);
        $(".modal .btn.btn-default").text(self.cancel_value);
        $(".modal .btn.btn-primary").text(self.confirm_value);
    };

    this.setModalData = function(id, value) {
        $(".modal .modal-body").html(self.body_value + ' <b>' + value + '</b>' + '?');
        $(".modal .btn.btn-primary").attr('data-id', id);
    };

    this.deleteData = function(id) {
        $.post(
            '/admin/ajax/deleteData',
            {
                table_name: self.table_name,
                id: id
            },
            function(data) {
                location.reload();
            },
            'json'
        );
    };
};