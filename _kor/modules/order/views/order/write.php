<?php echo validation_errors(); ?>
<div class="table_wrap iptForm">
    <?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm')); ?>
    <input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
    <input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />
    <legend>입력 폼</legend>
    <fieldset>
        <table class="bbs_table">
            <caption>원스톱 입력란</caption>
            <colgroup>
                <col width="160px" /><col />
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="subject">제목</label></th>
                <td>
                    <div class="bbs_titleipt use_category">
                        <div class="select_box" style="width: 15%;">
                            <label for="category">발주상태</label>
                            <select id="category" name="type" class="info_select">
                                <option value="">-- 발주상태 --</option>
                                <option value="대기" <?php echo set_select('type', '대기', ($data['type']=='대기')); ?>>발주대기</option>
                                <option value="완료" <?php echo set_select('type', '완료', ($data['type']=='완료')); ?>>발주완료</option>
                                <option value="취소" <?php echo set_select('type', '취소', ($data['type']=='취소')); ?>>발주취소</option>
                            </select>
                        </div>
                        <div><input type="text" id="title" name="title" class="form-control input-sm required" style="width: 85%" value="<?php echo set_value('title', $data['title']); ?>" placeholder="제목 입력" title="제목 입력" /></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="editor">
                    <?php
                    $params = array(
                        'input' => 'contents',
                        'pid' => $data['id']
                    );
                    echo CI::$APP->_editor($params);
                    ?>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="table_footer">
            <p class="btn_wrap btn_sm text-right">
                <button type="submit" class="btn btn-sm btn-modify"><span class="icon-edit">발주신청</span></button>
            </p>
        </div>
    </fieldset>
    </form>
</div>