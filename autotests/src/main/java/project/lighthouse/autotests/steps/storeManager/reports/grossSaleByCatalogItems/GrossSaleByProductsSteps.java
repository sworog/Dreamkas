package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import project.lighthouse.autotests.fixtures.sprint_25.Us_57_4_Fixture;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectValueColorable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.GrossSaleByProductsPage;

public class GrossSaleByProductsSteps extends ScenarioSteps {

    GrossSaleByProductsPage grossSaleByProductsPage;

    private Us_57_4_Fixture us_57_4_fixture = new Us_57_4_Fixture();

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void compareIsZeroSales() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getEmptyFixtureExampleTable());
    }

    @Step
    public void compareTableIfBarcodeIsEmpty() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getFixtureExampleTableForCheckingDataIfProductBarcodeIsEmpty());
    }

    @Step
    public void compareTableContainsCorrectDataForProduct1() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getExampleTableFixtureForProduct1());
    }

    @Step
    public void compareTableContainsCorrectDataForProduct2() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getExampleTableFixtureForProduct2());

    }

    @Step
    public void compareTableContainsEmptyDataForProduc1() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getEmptyExampleTableFixtureForProduct1());
    }

    @Step
    public void compareTableContainsEmptyDataForProduc2() {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(us_57_4_fixture.getEmptyExampleTableFixtureForProduct2());
    }

    @Step
    public void checkTheTableValueIsRed(String locator) {
        Assert.assertTrue(isTableColorRedByLocator(locator));
    }

    @Step
    public void checkTheTableValueColorIsNotRed(String locator) {
        Assert.assertFalse(isTableColorRedByLocator(locator));
    }

    private Boolean isTableColorRedByLocator(String locator) {
        return ((ObjectValueColorable) grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().getAbstractObjectByLocator(locator)).isValueColor();
    }
}
