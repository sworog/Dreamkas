package project.lighthouse.autotests.objects.error;

import org.openqa.selenium.WebElement;

public class ValidationError {

    private String message;

    public String getMessage() {
        return message;
    }

    public ValidationError(WebElement element) {
        message = element.getAttribute("data-error");
    }
}
