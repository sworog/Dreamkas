package project.lighthouse.autotests.jbehave.catalog;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.catalog.CatalogSteps;

public class GivenCatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @Given("the user opens catalog page")
    public void givenTheUserOpensCatalogPage() {
        catalogSteps.openCatalogPage();
    }
}
