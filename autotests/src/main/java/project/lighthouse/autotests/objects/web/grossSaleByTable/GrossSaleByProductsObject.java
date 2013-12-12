package project.lighthouse.autotests.objects.web.grossSaleByTable;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.objects.web.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.objectInterfaces.ResultComparable;

import java.util.Map;

public class GrossSaleByProductsObject extends AbstractObject implements ObjectLocatable, ResultComparable {

    private String name;
    private String todayValue;
    private String yesterdayValue;
    private String weekAgoValue;

    public GrossSaleByProductsObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("")).getText();
        todayValue = getElement().findElement(By.name("")).getText();
        yesterdayValue = getElement().findElement(By.name("")).getText();
        weekAgoValue = getElement().findElement(By.name("")).getText();
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
}
