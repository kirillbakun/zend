$(document).ready(function() {
    $(".actions .fa.fa-trash-o").click(function() {
        var action_block = $(this).parents(".actions");
        $(this).hide();
        action_block.children(".edit").hide();
        action_block.children(".fa.fa-arrow-left").show();
        action_block.children(".red").show();
    });

    $(".actions .fa.fa-arrow-left").click(function() {
        var action_block = $(this).parents(".actions");
        $(this).hide();
        action_block.children(".red").hide();
        action_block.children(".edit").show();
        action_block.children(".actions .fa.fa-trash-o").show();
    });
});