package project.lighthouse.autotests.steps.commercialManager.supplier;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPageMenuNavigation;

public class SupplierMenuNavigationSteps extends ScenarioSteps {

    SupplierPageMenuNavigation supplierPageMenuNavigation;

    @Step
    public void createSupplierLinkClick() {
        supplierPageMenuNavigation.createSupplierLinkClick();
    }

    @Step
    public void assertCreateSupplierLinkIsNotVisible() {
        try {
            supplierPageMenuNavigation.createSupplierLinkClick();
            Assert.fail("The create supplier link on supplier page menu navigation is visible and clickable");
        } catch (Exception ignored) {
        }
    }
}
