package project.lighthouse.autotests.objects.web.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class StoreGrossSaleByHourElement extends AbstractObjectNode {

    private String date;
    private String todayValue;
    private String yesterdayValue;
    private String weekAgoValue;

    public StoreGrossSaleByHourElement(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        date = getElement().findElement(By.xpath("")).getText();
        todayValue = getElement().findElement(By.xpath("")).getText();
        yesterdayValue = getElement().findElement(By.xpath("")).getText();
        weekAgoValue = getElement().findElement(By.xpath("")).getText();
    }

    @Override
    public String getObjectLocator() {
        return date;
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return date.equals(row.get("date")) &&
                todayValue.equals(row.get("todayValue")) &&
                yesterdayValue.equals(row.get("yesterdayValue")) &&
                weekAgoValue.equals(row.get("weekAgoValue."));
    }
}
