package project.lighthouse.autotests.collection.error;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.common.Waiter;
import project.lighthouse.autotests.storage.Storage;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class ValidationErrorsCollection extends AbstractObjectCollection<ValidationError> {

    public ValidationErrorsCollection(WebDriver webDriver) {
        //TODO investigate with BO about empty data-error attribute in invoice validation
        super(webDriver, By.xpath("//*[contains(@class, 'form__errorMessage_visible')]"));
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        Integer defaultValidationErrorTimeOut =
                Storage.getConfigurationVariableStorage().getTimeOutProperty("default.validation.error.timeout");
        List<WebElement> webElementList =
                new Waiter(webDriver, defaultValidationErrorTimeOut).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            ValidationError abstractObject = createNode(element);
            add(abstractObject);
        }
    }

    @Override
    public ValidationError createNode(WebElement element) {
        return new ValidationError(element);
    }

    /**
     * use {@link #compareWithExampleTable(org.jbehave.core.model.ExamplesTable)}}
     */
    @Deprecated
    public void matchesWithExampleTable(ExamplesTable examplesTable) {
        List<String> notFoundMessages = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            String message = row.get("error message");
            for (AbstractObject abstractObject : this) {
                ValidationError validationError = (ValidationError) abstractObject;
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
        for (AbstractObject abstractObject : this) {
            ValidationError validationError = (ValidationError) abstractObject;
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
            for (AbstractObject abstractObject : this) {
                ValidationError validationError = (ValidationError) abstractObject;
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
        for (AbstractObject abstractObject : this) {
            ValidationError validationError = (ValidationError) abstractObject;
            actualMessages.add(validationError.getMessage());
        }
        return actualMessages;
    }
}
