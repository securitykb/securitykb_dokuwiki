function addBtnActionTimestamp($btn, props, edid) {
    $btn.click(function() {
        jQuery.post(
            DOKU_BASE + 'lib/exe/ajax.php',
            {call: 'timestamp'},
            function(data) {
                // Insert the timestamp
                insertAtCarret(edid,fixtxt(data));
                // Close out of the picker and return
                pickerClose();
            },
            'text'
        );
    });
    return 'timestamp';
}
