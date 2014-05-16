package project.lighthouse.autotests.objects.web.error;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class ValidationError extends AbstractObject implements ResultComparable {

    private String message;

    public ValidationError(WebElement element) {
        super(element);
    }

    public String getMessage() {
        return message;
    }

    @Override
    public void setProperties() {
        message = getElement().getAttribute("data-error");
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults().compare("error message", message, row.get("error message"));
    }
}
