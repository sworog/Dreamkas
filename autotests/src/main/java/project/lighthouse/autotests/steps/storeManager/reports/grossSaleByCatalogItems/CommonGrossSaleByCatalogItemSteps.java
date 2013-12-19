package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import project.lighthouse.autotests.fixtures.Us_57_3_Fixture;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectValueColorable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemPage;

public class CommonGrossSaleByCatalogItemSteps {

    CommonGrossSaleByCatalogItemPage commonGrossSaleByCatalogItemPage;

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void compareWithExampleTableIncludingZeroSales() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getEmptyFixtureExampleTable());
    }

    @Step
    public void clickByLocator(String locator) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().clickByLocator(locator);
    }

    @Step
    public void checkTheTableValueIsRed(String locator) {
        Assert.assertTrue(getTableColorByLocator(locator));
    }

    @Step
    public void checkTheTableValueColorIsNotRed(String locator) {
        Assert.assertFalse(getTableColorByLocator(locator));
    }

    private Boolean getTableColorByLocator(String locator) {
        return ((ObjectValueColorable) commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().getAbstractObjectByLocator(locator)).isValueColor();
    }
}
