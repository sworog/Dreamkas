package project.lighthouse.autotests.steps.commercialManager.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPageMenuNavigation;

public class SupplierMenuNavigationSteps extends ScenarioSteps {

    SupplierPageMenuNavigation supplierPageMenuNavigation;

    @Step
    public void createSupplierLinkClick() {
        supplierPageMenuNavigation.createSupplierLinkClick();
    }
}
