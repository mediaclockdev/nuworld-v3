$('.select2').select2({
  placeholder: "Select Icon",
});
$(document).on("select2:open", () => {
  document.querySelector(".select2-container--open .select2-search__field").focus()
});

$('#remixicon').select2({
  width: '100%',
  templateResult: formatIcon,
  templateSelection: formatIcon,
  escapeMarkup: function (markup) {
    return markup;
  }
});

function formatIcon(option) {
  if (!option.id) {
    return option.text;
  }
  var icon = $(option.element).data('icon');
  var text = option.text;
  return `<i class="ri-${icon}" style="margin-right: 10px; font-size: 24px"></i>${text}`;
}
