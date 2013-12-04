package project.lighthouse.autotests.steps.commercialManager.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.fixtures.Us_55_2_Fixture;
import project.lighthouse.autotests.pages.commercialManager.reports.GrossSaleByStoresReportsPage;

public class GrossSaleByStoreReportsSteps extends ScenarioSteps {

    GrossSaleByStoresReportsPage grossSaleByStoresReportsPage;

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
}
