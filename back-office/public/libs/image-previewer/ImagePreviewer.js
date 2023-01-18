class ImagePreviewer {
  constructor(e, options = {}) {
    this.options = options;
    this.errors = new Object();
    this.imageContainer = document.querySelector("img.previewer-image");
    this.previewImage(e);
  }

  getTrustedImageTypes() {
    return ["jpg", "jpeg", "svg", "gif", "png", "avif", "webp", "apng"];
  }

  checkImage(image) {
    const extension = image.name.split(".").pop();
    if (!this.getTrustedImageTypes().includes(extension)) {
      this.errors = { ...this.errors, type: "Cette image n'est pas valide !" };
    }
    this.checkImageSize(image);
  }

  previewImage(e) {
    const image = e.target.files[0];
    const node = e.target;

    this.checkImage(image);

    if (Object.keys(this.errors).length > 0) {
      return this.displayErrorsMsg(node);
    }

    return this.preview(image, node);
  }

  checkImageSize(image) {
    if (this.options.hasOwnProperty("maxFileSize")) {
      const allowedSize = this.convertMbToBytes(this.options.maxFileSize);
      if (image.size > allowedSize) {
        this.errors = {
          ...this.errors,
          fileSize: "Ce fichier est trop volumineux !",
        };
      }
    }
  }

  displayErrorsMsg(node) {
    let target = document.querySelector("#errorsContainer"),
      container,
      content = "";

    if (target !== null && target !== undefined) {
      container = target;
    } else if (this.options.hasOwnProperty("errorsContainer")) {
      container = document.querySelector(this.options.errorsContainer);
    } else if (container === null || container === undefined) {
      container = document.querySelector(this.options.errorsContainer);
      if (container === null || container === undefined) {
        container = document.createElement("div");
        container.setAttribute("id", "errorsContainer");
        container.classList = "previewer-error list";
        node.insertAdjacentElement("beforebegin", container);
      }
    } else if (target !== null && target !== undefined) {
      container = target;
    }

    if (Object.keys(this.errors).length === 0) {
      container.remove();
      return null;
    }
    if (this.imageContainer !== undefined && this.imageContainer !== null) {
      this.imageContainer.remove();
    }
    for (const key in this.errors) {
      if (this.errors.hasOwnProperty(key)) {
        content += `<p class="previewer-error item">${key} : ${this.errors[key]}</p>`;
      }
    }

    return (container.innerHTML = content);
  }

  convertMbToBytes(size = 0, type = "MB") {
    const types = ["B", "KB", "MB", "GB", "TB"];

    const key = types.indexOf(type.toUpperCase());

    if (typeof key !== "boolean") {
      return parseInt(size) * 1024 ** key;
    }
    return "invalid type: type must be GB/KB/MB etc.";
  }

  preview(image, node) {
    let imageContainer = this.imageContainer;
    if (this.imageContainer === undefined || this.imageContainer === null) {
      imageContainer = document.createElement("img");
      imageContainer.classList = "previewer-image";
      node.insertAdjacentElement("beforebegin", imageContainer);
      this.imageContainer = imageContainer;
    }

    if (this.imageContainer.style.display === "none") {
      this.imageContainer.style.display = "block";
    }

    this.imageContainer.src = URL.createObjectURL(image);
    this.imageContainer.srcset = URL.createObjectURL(image);
    return (node.onload = () => URL.revokeObjectURL(this.imageContainer.src)); // Free memory
  }
}
