(() => {
  let currentStep = document.querySelector(".step:first-child");
  const currentStepOffset = parseInt(currentStep.dataset.stepIndex);
  const stepNavButton = document.querySelectorAll("[data-step-nav]");

  if (stepNavButton) {
    stepNavButton.forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        const direction = button.dataset.stepNav;
        const nextStep = document.querySelector(
          `[data-step-index="${currentStepOffset + 1}"]`
        );
        const prevStep = document.querySelector(
          `[data-step-index="${currentStepOffset - 1}"]`
        );
        console.log({ currentStepOffset, direction, nextStep, prevStep });
        if (direction === "next") {
          if (nextStep) {
            currentStep.style.display = "none";
            nextStep.style.display = "unset";
            document.querySelector('[data-step-nav="prev"]').style.display =
              "unset";
          } else {
            button.style.display = "none";
          }
          currentStep = currentStepOffset;
          return;
        }
        if (direction === "prev") {
          if (prevStep) {
            currentStep.style.display = "none";
            prevStep.style.display = "unset";
            button.style.display = "unset";
          } else {
            button.style.display = "none";
          }
          currentStep = currentStepOffset;
        }
      });
    });
  }

  console.warn({ currentStep, stepNavButton });
})();
