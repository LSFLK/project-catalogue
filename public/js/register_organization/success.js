function copyLinkToClipboard() {
    const button = document.getElementById("copy_link");
    const link = button.getAttribute('link');
    const element = document.createElement('input');

    element.value = link;
    document.body.appendChild(element);
    element.select();
    document.execCommand('copy');
    document.body.removeChild(element);

    alert("Organization link has been copied to clipboard.");
}