package project.lighthouse.autotests.objects.web.reports.grossSaleByStores;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class GrossSaleByStoresObject extends AbstractObject implements ObjectLocatable, ResultComparable {

    private String storeNumber;
    private String yesterdayValue;
    private String twoDaysAgoValue;
    private String eightDaysAgoValue;


    public GrossSaleByStoresObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        storeNumber = getElement().findElement(By.name("store.number")).getText();
        yesterdayValue = getElement().findElement(By.name("yesterday.runningSum")).getText();
        twoDaysAgoValue = getElement().findElement(By.name("twoDaysAgo.runningSum")).getText();
        eightDaysAgoValue = getElement().findElement(By.name("eightDaysAgo.runningSum")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("storeNumber", storeNumber, row.get("storeNumber"))
                .compare("yesterdayValue", yesterdayValue, row.get("yesterdayValue"))
                .compare("twoDaysAgoValue", twoDaysAgoValue, row.get("twoDaysAgoValue"))
                .compare("eightDaysAgoValue", eightDaysAgoValue, row.get("eightDaysAgoValue"));
    }

    @Override
    public String getObjectLocator() {
        return storeNumber;
    }
}
