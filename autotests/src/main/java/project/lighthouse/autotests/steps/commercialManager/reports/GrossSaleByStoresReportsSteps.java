package project.lighthouse.autotests.steps.commercialManager.reports;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.Us_55_2_Fixture;
import project.lighthouse.autotests.objects.web.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.reports.grossSaleByStores.GrossSalesByStoresCollection;
import project.lighthouse.autotests.pages.commercialManager.reports.GrossSaleByStoresReportsPage;

public class GrossSaleByStoresReportsSteps extends ScenarioSteps {

    GrossSaleByStoresReportsPage grossSaleByStoresReportsPage;

    @Step
    public void openGrossSaleByStoresReportsPage() {
        grossSaleByStoresReportsPage.open();
    }

    @Step
    public void compareWithExampleTable() {
        ExamplesTable examplesTable = new Us_55_2_Fixture().getFixtureExampleTable();
        grossSaleByStoresReportsPage.getStoreGrossSaleByHourElementCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void compareWithExampleToCheckZeroSales() {
        ExamplesTable examplesTable = new Us_55_2_Fixture().getFixtureExampleTableToCheckZeroSale();
        grossSaleByStoresReportsPage.getStoreGrossSaleByHourElementCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void assertYesterdayRowName() {
        Assert.assertEquals("вчера", grossSaleByStoresReportsPage.getYesterdayRowWebElement().getText());
    }

    @Step
    public void assertTwoDaysAgoRowName() {
        Assert.assertEquals("позавчера", grossSaleByStoresReportsPage.getTwoDaysAgoRowWebElement().getText());
    }

    @Step
    public void assertEightDaysAgoRowName() {
        Assert.assertEquals(getDate(), grossSaleByStoresReportsPage.getEightDaysAgoRowWebElement().getText());
    }

    @Step
    public void assertYesterdayRowSort() {
        grossSaleByStoresReportsPage.getYesterdayRowWebElement().click();
        GrossSalesByStoresCollection collection = grossSaleByStoresReportsPage.getStoreGrossSaleByHourElementCollection();
        Assert.assertEquals("245521", ((ObjectLocatable) collection.get(0)).getObjectLocator());
        Assert.assertEquals("245522", ((ObjectLocatable) collection.get(1)).getObjectLocator());
        Assert.assertEquals("2455222", ((ObjectLocatable) collection.get(2)).getObjectLocator());
        Assert.assertEquals("2455212", ((ObjectLocatable) collection.get(3)).getObjectLocator());
    }

    @Step
    public void assertTwoDaysAgoRowSort() {
        grossSaleByStoresReportsPage.getTwoDaysAgoRowWebElement().click();
        GrossSalesByStoresCollection collection = grossSaleByStoresReportsPage.getStoreGrossSaleByHourElementCollection();
        Assert.assertEquals("245522", ((ObjectLocatable) collection.get(0)).getObjectLocator());
        Assert.assertEquals("245521", ((ObjectLocatable) collection.get(1)).getObjectLocator());
        Assert.assertEquals("2455222", ((ObjectLocatable) collection.get(2)).getObjectLocator());
        Assert.assertEquals("2455212", ((ObjectLocatable) collection.get(3)).getObjectLocator());
    }

    @Step
    public void assertEightDaysAgoRowSort() {
        grossSaleByStoresReportsPage.getEightDaysAgoRowWebElement().click();
        GrossSalesByStoresCollection collection = grossSaleByStoresReportsPage.getStoreGrossSaleByHourElementCollection();
        Assert.assertEquals("245522", ((ObjectLocatable) collection.get(0)).getObjectLocator());
        Assert.assertEquals("245521", ((ObjectLocatable) collection.get(1)).getObjectLocator());
        Assert.assertEquals("2455222", ((ObjectLocatable) collection.get(2)).getObjectLocator());
        Assert.assertEquals("2455212", ((ObjectLocatable) collection.get(3)).getObjectLocator());
    }

    private String getDate() {
        switch (new DateTime().getDayOfWeek() - 1) {
            case 0:
                return "прошлое воскресение";
            case 1:
                return "прошлый понедельник";
            case 2:
                return "прошлый вторник";
            case 3:
                return "прошлая среда";
            case 4:
                return "прошлый четверг";
            case 5:
                return "прошлая пятница";
            case 6:
                return "прошлая суббота";
            default:
                return "в аду";
        }
    }
}
