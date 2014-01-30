package project.lighthouse.autotests.objects.web.error;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.Waiter;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class ValidationErrorsCollection extends ArrayList<ValidationError> {

    public ValidationErrorsCollection(WebDriver webDriver) {
        Waiter waiter = new Waiter(webDriver, StaticData.DEFAULT_VALIDATION_ERROR_TIMEOUT);
        List<WebElement> webElementList = waiter.getVisibleWebElements(By.xpath("//*[@data-error]"));
        for (WebElement element : webElementList) {
            ValidationError validationError = new ValidationError(element);
            add(validationError);
        }
    }

    public void matchesWithExampleTable(ExamplesTable examplesTable) {
        List<String> notFoundMessages = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            String message = row.get("error message");
            for (ValidationError validationError : this) {
                if (validationError.getMessage().equals(message)) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                notFoundMessages.add(message);
            }
        }
        if (notFoundMessages.size() > 0) {
            String errorMessage = String.format("Not found messages: '%s'. Messages on the page: '%s'", notFoundMessages, getActualMessages());
            Assert.fail(errorMessage);
        }
    }

    public void matchesWithMessage(String message) {
        Boolean matches = false;
        for (ValidationError validationError : this) {
            if (validationError.getMessage().equals(message)) {
                matches = true;
            }
        }
        if (!matches) {
            String errorMessage = String.format("No message with text '%s' found. Messages on the page: '%s'.", message, getActualMessages());
            Assert.fail(errorMessage);
        }
    }

    public void notMatchesWithExampleTable(ExamplesTable examplesTable) {
        List<String> messages = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            String message = row.get("error message");
            for (ValidationError validationError : this) {
                if (validationError.getMessage().equals(message)) {
                    messages.add(validationError.getMessage());
                }
            }
        }
        if (messages.size() > 0) {
            String errorMessage = String.format("Found messages: '%s'. Messages on the page: '%s'", messages, getActualMessages());
            Assert.fail(errorMessage);
        }
    }

    public List<String> getActualMessages() {
        List<String> actualMessages = new ArrayList<>();
        for (ValidationError validationError : this) {
            actualMessages.add(validationError.getMessage());
        }
        return actualMessages;
    }
}
