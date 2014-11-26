package ru.dreamkas.pos.controller.requests;
import ru.dreamkas.pos.model.api.GroupApiObject;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.StoreApiObject;

public class CreateGroupRequest extends BaseRequest<NamedObject>{
    private GroupApiObject mGroup;

    public CreateGroupRequest(){
        super(NamedObject.class);
    }

    public void setGroup(GroupApiObject group){
        this.mGroup = group;
    }

    @Override
    public NamedObject loadDataFromNetwork() throws Exception
    {
        NamedObject store = mRestClient.createGroup(mGroup);
        return store;
    }
}
