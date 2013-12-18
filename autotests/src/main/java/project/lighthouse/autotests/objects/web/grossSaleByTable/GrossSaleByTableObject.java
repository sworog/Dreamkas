package project.lighthouse.autotests.objects.web.grossSaleByTable;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class GrossSaleByTableObject extends AbstractObject implements ObjectLocatable, ResultComparable, ObjectClickable {

    private String name;
    private String todayValue;
    private String yesterdayValue;
    private String weekAgoValue;

    public GrossSaleByTableObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.xpath(".//*[contains(@name,'.name')]")).getText();
        todayValue = getElement().findElement(By.name("today.runningSum")).getText();
        yesterdayValue = getElement().findElement(By.name("yesterday.runningSum")).getText();
        weekAgoValue = getElement().findElement(By.name("weekAgo.runningSum")).getText();
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("todayValue", todayValue, row.get("todayValue"))
                .compare("yesterdayValue", yesterdayValue, row.get("yesterdayValue"))
                .compare("weekAgoValue", weekAgoValue, row.get("weekAgoValue"));
    }

    @Override
    public void click() {
        getElement().click();
    }
}
