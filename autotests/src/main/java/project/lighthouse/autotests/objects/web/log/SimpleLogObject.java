package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class SimpleLogObject extends AbstractObject implements ResultComparable {

    private String message;

    public SimpleLogObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        message = setProperty(By.xpath(".//*[@class='log__finalMessage']"));
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compareContain("logMessage", message, row.get("logMessage"));
    }
}
