package project.lighthouse.autotests.jbehave.store;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.store.StoreSteps;

public class GivenStoreUserSteps {

    @Steps
    StoreSteps storeSteps;

    @Given("the user opens the stores list page")
    public void givenTheUserOpensTheStoresListPage() {
        storeSteps.storeListPageOpen();
    }
}
