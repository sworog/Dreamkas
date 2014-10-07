package project.lighthouse.autotests.common.objects;

public interface ModalWindowPageObject extends GeneralPageObject {

    public String modalWindowXpath();

    public void confirmationOkClick();

    public void deleteButtonClick();

    public void confirmDeleteButtonClick();
}
