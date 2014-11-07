package ru.dreamkas.collection.error;

import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

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
        message = getElement().getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults().compare("error message", message, row.get("error message"));
    }
}
