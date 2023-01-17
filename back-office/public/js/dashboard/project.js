const fileInput = document.querySelector("#project_uploadedFile");

fileInput.addEventListener("change", (e) => {
  if (e.target.files.length > 0) {
    return new ImagePreviewer(e, {
      maxFileSize: 20, // en mb
    });
  }

  return (document.querySelector("#error").innerText =
    "Aucune image n'a été chargée !");
});
