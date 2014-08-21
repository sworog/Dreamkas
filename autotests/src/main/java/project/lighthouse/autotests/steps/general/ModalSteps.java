package project.lighthouse.autotests.steps.general;

import project.lighthouse.autotests.common.ModalWindowPageObject;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInEditModalWindow;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ModalSteps extends AbstractGeneralSteps<ModalWindowPageObject> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("модальное окно создания оприходывания", StockInCreateModalWindow.class);
            put("модальное окно редактирования оприходывания", StockInEditModalWindow.class);
        }};
    }

    public void assertTitle(String title) {
        assertThat(getCurrentPageObject().getTitle(), is(title));
        getCurrentPageObject().getTitle();
    }
}
