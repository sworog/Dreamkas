package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.fixtures.sprint_25.Us_57_3_Fixture;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectValueColorable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemPage;

public class CommonGrossSaleByCatalogItemSteps extends ScenarioSteps {

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
        new BodyPreLoader(getDriver()).await();
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

    @Step
    public void compareWithExampleTableForSubCategory1Shop1() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop1SubCategory1());
    }

    @Step
    public void compareWithExampleTableForGroup2Shop1() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop1Group2());
    }

    @Step
    public void compareWithExampleTableForCategory2Shop1() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop1Category2());
    }

    @Step
    public void compareWithExampleTableForSubCategory2Shop1() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop1SubCategory2());
    }

    @Step
    public void compareWithExampleTableForGroup1Shop2() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop2Group1());
    }

    @Step
    public void compareWithExampleTableForCategory1Shop2() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop2Category1());
    }

    @Step
    public void compareWithExampleTableForSubCategory1Shop2() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop2SubCategory1());
    }

    @Step
    public void compareWithExampleTableForSubCategory2Shop2() {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(new Us_57_3_Fixture().getExampleTableForShop2Product2());
    }
}
