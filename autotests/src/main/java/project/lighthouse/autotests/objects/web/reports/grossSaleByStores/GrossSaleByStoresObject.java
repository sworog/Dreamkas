package project.lighthouse.autotests.objects.web.reports.grossSaleByStores;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class GrossSaleByStoresObject extends AbstractObjectNode {

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
    public Boolean rowIsEqual(Map<String, String> row) {
        return storeNumber.equals(row.get("storeNumber")) &&
                yesterdayValue.equals(row.get("yesterdayValue")) &&
                twoDaysAgoValue.equals(row.get("twoDaysAgoValue")) &&
                eightDaysAgoValue.equals(row.get("eightDaysAgoValue"));
    }

    @Override
    public String getObjectLocator() {
        return storeNumber;
    }
}
