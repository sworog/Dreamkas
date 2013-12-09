package project.lighthouse.autotests.objects.web.reports.storeGrossSaleBuHour;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

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
        date = getElement().findElement(By.name("dayHour")).getText();
        todayValue = getElement().findElement(By.name("today.hourSum")).getText();
        yesterdayValue = getElement().findElement(By.name("yesterday.hourSum")).getText();
        weekAgoValue = getElement().findElement(By.name("weekAgo.hourSum")).getText();
    }

    @Override
    public String getObjectLocator() {
        return date;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("date", date, row.get("date"))
                .compare("todayValue", todayValue, row.get("todayValue"))
                .compare("yesterdayValue", yesterdayValue, row.get("yesterdayValue"))
                .compare("weekAgoValue", weekAgoValue, row.get("weekAgoValue"));
    }
}
