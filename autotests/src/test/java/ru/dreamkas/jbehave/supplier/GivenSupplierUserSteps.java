package ru.dreamkas.jbehave.supplier;

import net.thucydides.core.annotations.Steps;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.steps.supplier.SupplierSteps;

public class GivenSupplierUserSteps extends ScenarioSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Given("the user opens the supplier list page")
    @Alias("пользователь открывает страницу поставщиков")
    public void givenTheUserOpensTheSupplierListPage() {
        supplierSteps.supplierListPageOpen();
    }
}
