package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.DashBoardSteps;

public class DashboarUserSteps {

    @Steps
    DashBoardSteps dashBoardSteps;

    @When("the user opens the products section on the dashboard page")
    public void whenTheUserOpensTheProductsSectionOnTheDashboarPage() {
        dashBoardSteps.productSectionButtonClick();
    }

    @When("the user opens the catalog section on the dashboard page")
    public void whenTheUserOpensTheCatalogSectionOnTheDashboarPage() {
        dashBoardSteps.catalogSectionButtonClick();
    }

    @When("the user opens the invoices section on the dashboard page")
    public void whenTheUserOpensTheInvoicesSectionOnTheDashboarPage() {
        dashBoardSteps.invoicesSectionButtonClick();
    }

    @When("the user opens the balance section on the dashboard page")
    public void whenTheUserOpensTheBalanceSectionOnTheDashboarPage() {
        dashBoardSteps.balanceSectionButtonClick();
    }

    @When("the user opens the writeOffs section on the dashboard page")
    public void whenTheUserOpensTheWriteOffsSectionOnTheDashboarPage() {
        dashBoardSteps.writeOffsSectionButtonClick();
    }

    @When("the user opens the users section on the dashboard page")
    public void whenTheUserOpensTheUsersSectionOnTheDashboarPage() {
        dashBoardSteps.userSectionButtonClick();
    }
}
