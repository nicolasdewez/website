function EstimateEdit(collection, idLines, addLink, deleteLink, quantity, unitPrice, total, totalEstimate)
{
    this.collection = collection;
    this.idLines = idLines;
    this.addLink = addLink;
    this.deleteLink = deleteLink;
    this.quantity = quantity;
    this.unitPrice = unitPrice;
    this.total = total;
    this.totalEstimate = totalEstimate;

    // Calculate index for new element in collection
    this.collection.data('index', this.collection.find('select').length);

    this.onClickAddLink = function(e){
        e.preventDefault();
        var prototype = this.collection.data('prototype');
        var index = this.collection.children().children().length;
        var newForm = prototype.replace(/__name__/g, index);

        this.collection.data('index', index + 1);

        this.collection.find('#' + this.idLines).append(newForm);

        $(document).on('click', '.glyphicon-trash', this.onClickDeleteLink.bind(this));
        $(document).on('change', '.collection-quantity', this.onChangeQuantity.bind(this));
        $(document).on('change', '.collection-unit-price', this.onChangeUnitPrice.bind(this));
    };

    this.onClickDeleteLink = function(e) {
        e.preventDefault();
        var element = $(e.currentTarget);
        element.parent().parent().parent().remove();
        this.onChangeTotal();

        return false;
    };

    this.onChangeQuantity = function(e) {
        e.preventDefault();
        var element = $(e.currentTarget).find('input');
        var unitPrice = element.parent().parent().parent().find('.collection-unit-price').find('input');
        this.calculateTotalLine(
            element.parent().parent().parent().find('.collection-total').find('input'),
            element,
            unitPrice
        );
        this.onChangeTotal();
    };

    this.onChangeUnitPrice = function(e) {
        e.preventDefault();
        var element = $(e.currentTarget).find('input');
        var quantity = element.parent().parent().parent().find('.collection-quantity').find('input');
        this.calculateTotalLine(
            element.parent().parent().parent().find('.collection-total').find('input'),
            quantity,
            element
        );
        this.onChangeTotal();
    };

    this.calculateTotalLine = function(fieldTotal, fieldQuantity, fieldUnitPrice) {
        fieldTotal.val(fieldUnitPrice.val() * fieldQuantity.val());
    };

    this.onChangeTotal = function() {
        var total = 0;
        $('.collection-total').each(function(key, element) {
            total = total + parseFloat($(element).find('input').val());
        });
        this.totalEstimate.val(total);
    };

    this.initialize = function() {
        this.total.each(function(key, element) {
            var quantity = $(element).parent().find('.collection-quantity').find('input');
            var unitPrice = $(element).parent().find('.collection-unit-price').find('input');
            this.calculateTotalLine(
                $(element).find('input'),
                quantity,
                unitPrice
            );
        }.bind(this));

        this.onChangeTotal();
    };

    this.listenEvents = function() {
        this.addLink.click(this.onClickAddLink.bind(this));
        this.deleteLink.on('click', this.onClickDeleteLink.bind(this));
        this.quantity.change(this.onChangeQuantity.bind(this));
        this.unitPrice.change(this.onChangeUnitPrice.bind(this));
    };

    this.initialize();
    this.listenEvents();
}
