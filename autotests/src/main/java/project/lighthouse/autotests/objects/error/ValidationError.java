package project.lighthouse.autotests.objects.error;

import org.openqa.selenium.WebElement;

public class ValidationError {

    private String message;

    public String getMessage() {
        return message;
    }

    private WebElement element;

    public ValidationError(WebElement element) {
        this.element = element;
        setProperties();
    }

    private void setProperties() {
        message = element.getAttribute("data-error");
    }
}
